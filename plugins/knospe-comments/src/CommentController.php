<?php

declare(strict_types=1);

namespace KnospeComments;

use Knospe\Core\Exception\ForbiddenException;
use Knospe\Core\Exception\ValidationException;
use Knospe\Http\Input;
use Knospe\Http\Json;
use Knospe\Plugin\Hook\HookDispatcher;
use Knospe\Support\AuthContext;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Steuert die Kommentar-Endpunkte. Wird über den Container aufgelöst, seine
 * Abhängigkeiten (PDO, Hook-Bus, Anmeldung) werden automatisch verkabelt -
 * ein Plugin-Controller funktioniert also genauso wie ein Kern-Handler.
 *
 * Lern mehr: ../../../docs/05-plugins/05-plugin-datenzugriff.md
 */
final class CommentController
{
    private const SELECT = 'SELECT c.id, c.post_id, c.author_id, c.content, c.created_at,
                                   u.display_name AS author_name
                            FROM comments c
                            JOIN users u ON u.id = c.author_id';

    public function __construct(
        private PDO $pdo,
        private HookDispatcher $hooks,
        private AuthContext $auth,
    ) {
    }

    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $postId = (int) ($request->getQueryParams()['post_id'] ?? 0);

        $stmt = $this->pdo->prepare(self::SELECT . ' WHERE c.post_id = ? ORDER BY c.created_at ASC');
        $stmt->execute([$postId]);

        return Json::response(['comments' => $stmt->fetchAll()]);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->auth->require();
        $data = Input::json($request);

        $postId = (int) ($data['post_id'] ?? 0);
        $content = $this->hooks->applyFilter('comment.content', (string) ($data['content'] ?? ''));

        if ($postId <= 0 || $content === '') {
            throw new ValidationException('Eingabe ungültig', [
                'content' => 'Bitte Beitrag und Text angeben.',
            ]);
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO comments (post_id, author_id, content) VALUES (?, ?, ?) RETURNING id',
        );
        $stmt->execute([$postId, $user->id, $content]);
        $id = (int) $stmt->fetchColumn();

        $stmt = $this->pdo->prepare(self::SELECT . ' WHERE c.id = ?');
        $stmt->execute([$id]);

        return Json::response(['comment' => $stmt->fetch()], 201);
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->auth->require();
        $id = (int) $request->getAttribute('id');

        $stmt = $this->pdo->prepare('SELECT author_id FROM comments WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return Json::response(['status' => 'nicht gefunden'], 404);
        }
        if ((int) $row['author_id'] !== $user->id) {
            throw new ForbiddenException('Nur der Autor darf diesen Kommentar löschen.');
        }

        $this->pdo->prepare('DELETE FROM comments WHERE id = ?')->execute([$id]);

        return Json::response(['status' => 'gelöscht']);
    }
}
