<?php


namespace App\Renderer;

use App\API\ResultInterface;

/**
 * Interface RendererInterface
 *
 * @package App\Renderer
 */
interface RendererInterface
{
    public function render(ResultInterface $item): void;
}
