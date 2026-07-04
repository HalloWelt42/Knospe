<?php

declare(strict_types=1);

namespace KnospeComments;

use Knospe\Plugin\AbstractPlugin;
use Knospe\Plugin\PluginManager;
use Knospe\Plugin\PluginMetadata;

/**
 * Beispiel-Plugin "Kommentare".
 *
 * Zeigt alle Fähigkeiten eines Knospe-Plugins:
 *  - eigene Routen (/api/comments ...)
 *  - eigene Migration (Tabelle comments, läuft beim Aktivieren)
 *  - eine Aktion (reagiert auf post.deleted)
 *  - einen Filter (comment.content)
 *  - einen Frontend-Teil (frontend/comments.plugin.ts + CommentsPanel.svelte)
 *
 * Zum Nachbauen: dieses Verzeichnis kopieren, id/namespace anpassen, loslegen.
 * Lern mehr: ./docs/05-plugins/04-dein-erstes-plugin.md
 */
final class CommentsPlugin extends AbstractPlugin
{
    public function metadata(): PluginMetadata
    {
        return new PluginMetadata(
            id: 'knospe-comments',
            name: 'Kommentare',
            version: '1.0.0',
            capabilities: ['routes', 'migrations', 'hooks', 'frontend'],
        );
    }

    public function boot(PluginManager $manager): void
    {
        // Eigene Routen registrieren.
        $manager->routes()->add('GET', '/api/comments', CommentController::class . '@index');
        $manager->routes()->add('POST', '/api/comments', CommentController::class . '@store');
        $manager->routes()->add('DELETE', '/api/comments/{id}', CommentController::class . '@destroy');

        // Aktion: auf das Löschen eines Beitrags reagieren. Die Fremdschlüssel
        // räumen dank ON DELETE CASCADE bereits mit auf - dieser Hook zeigt, wie
        // ein Plugin überhaupt auf Kern-Ereignisse reagieren kann.
        $manager->hooks()->addAction('post.deleted', static function (int $postId) use ($manager): void {
            $manager->db()->prepare('DELETE FROM comments WHERE post_id = ?')->execute([$postId]);
        });

        // Filter: den Kommentartext bereinigen. Andere Plugins könnten ihn
        // über denselben Filter zusätzlich verändern.
        $manager->hooks()->addFilter('comment.content', static fn (string $text): string => trim($text));
    }

    public function enable(PluginManager $manager): void
    {
        // Standard-Einstellung setzen (Beispiel).
        $manager->setOption('knospe-comments', 'moderation', false);
    }
}
