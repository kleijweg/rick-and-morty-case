<?php


namespace App\Renderer;

use App\API\ResultInterface;
use App\Model\NamedLink;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ModelRenderer
 * @package App\Renderer
 */
class ModelRenderer implements RendererInterface
{
    /**
     * Default mapping - every model has a name and an id
     *
     * @var array<mixed>
     */
    public $mapping = [
        'title' => 'name',
    ];

    /**
     * @var SymfonyStyle
     */
    private $style;

    /**
     * Renderer constructor.
     *
     * @param SymfonyStyle $style
     * @param array<mixed> $mapping
     */
    public function __construct(SymfonyStyle $style, array $mapping)
    {
        $this->style = $style;
        $this->mapping = $mapping;
    }

    /**
     * Render an item
     *
     * @param ResultInterface $item
     * @return void
     */
    public function render(ResultInterface $item): void
    {
        $this->style->title(sprintf('%s [%s]', $item->get($this->mapping['title']), $item->get('id')));
        foreach ($this->mapping['properties'] ?? [] as $property => $label) {
            $value = $item->get($property);
            $this->renderValue($label, $value);
        }

        if ($this->style->isDebug()) {
            dump($item);
        }
    }

    /**
     * Render a value with a label
     * @param string $label
     * @param mixed $value
     */
    private function renderValue(string $label, $value): void
    {
        // Arrays are only displayed when output is verbose.
        $isVerbose = $this->style->isVerbose();
        if (is_array($value)) {
            if ($isVerbose) {
                $this->style->writeln(sprintf('<info>%s:</info>', $label));
                $this->style->listing($value);
            }
            return;
        }

        // If output is verbose, display both name and URL of named links. If not, only display the name.
        if ($value instanceof NamedLink) {
            $this->style->writeln(
                $isVerbose ?
                    sprintf('<info>%s:</info> %s (%s)', $label, $value->getName(), $value->getUrl()) :
                    sprintf('<info>%s:</info> %s', $label, $value->getName())
            );
            return;
        }

        // Only display URLs if output is verbose.
        if ($this->isUrl($value)) {
            if ($isVerbose) {
                $this->style->writeln(sprintf('<info>%s:</info> %s', $label, $value));
            }
            return;
        }

        // Render everything else.
        $this->style->writeln(sprintf('<info>%s:</info> %s', $label, $value));
    }

    /**
     * Check if a value is an URL.
     *
     * @param mixed $value
     * @return bool
     */
    private function isUrl($value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_URL);
    }
}
