<?php

declare(strict_types=1);

namespace Knospe\Domain\Post;

use Knospe\Core\Exception\ForbiddenException;
use Knospe\Core\Exception\NotFoundException;
use Knospe\Core\Exception\ValidationException;

/**
 * Geschaeftslogik fuer Beitraege: Validierung, Rechtepruefung (nur der Autor
 * darf aendern/loeschen) und das Zusammenspiel mit dem Repository.
 *
 * Lern mehr: docs/02-architektur/05-services-pattern.md
 */
final class PostService
{
    public function __construct(private PostRepositoryInterface $posts)
    {
    }

    /**
     * @return list<Post>
     */
    public function list(): array
    {
        return $this->posts->recent();
    }

    public function get(int $id): Post
    {
        return $this->posts->findById($id)
            ?? throw new NotFoundException("Beitrag {$id} nicht gefunden.");
    }

    public function create(int $authorId, string $title, string $body): Post
    {
        [$title, $body] = $this->validate($title, $body);

        return $this->posts->create($authorId, $title, $body);
    }

    public function update(int $id, int $currentUserId, string $title, string $body): Post
    {
        $post = $this->get($id);
        $this->assertOwner($post, $currentUserId);
        [$title, $body] = $this->validate($title, $body);

        return $this->posts->update($id, $title, $body);
    }

    public function delete(int $id, int $currentUserId): void
    {
        $post = $this->get($id);
        $this->assertOwner($post, $currentUserId);
        $this->posts->delete($id);
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function validate(string $title, string $body): array
    {
        $title = trim($title);
        $body = trim($body);

        $errors = [];
        if ($title === '') {
            $errors['title'] = 'Bitte einen Titel angeben.';
        } elseif (mb_strlen($title) > 255) {
            $errors['title'] = 'Der Titel darf hoechstens 255 Zeichen haben.';
        }
        if ($body === '') {
            $errors['body'] = 'Bitte einen Text angeben.';
        }
        if ($errors !== []) {
            throw new ValidationException('Eingabe ungueltig', $errors);
        }

        return [$title, $body];
    }

    private function assertOwner(Post $post, int $currentUserId): void
    {
        if ($post->authorId !== $currentUserId) {
            throw new ForbiddenException('Nur der Autor darf diesen Beitrag aendern.');
        }
    }
}
