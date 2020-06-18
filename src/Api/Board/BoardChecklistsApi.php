<?php

declare(strict_types=1);

namespace Semaio\TrelloApi\Api\Board;

use Semaio\TrelloApi\Api\AbstractApi;

/**
 * @see https://trello.com/docs/api/board
 *
 * Fully implemented.
 */
class BoardChecklistsApi extends AbstractApi
{
    protected $path = 'boards/#id#/checklists';

    /**
     * Get cards related to a given board.
     *
     * @see https://trello.com/docs/api/board/#get-1-boards-board-id-cards
     */
    public function all(string $id, array $params = []): array
    {
        return $this->get($this->getPath($id), $params);
    }

    /**
     * Add an checklist to a given board.
     *
     * @see https://trello.com/docs/api/board/#post-1-boards-board-id-checklists
     */
    public function create(string $id, array $params): array
    {
        $this->validateRequiredParameters(['name'], $params);

        return $this->post($this->getPath($id), $params);
    }
}
