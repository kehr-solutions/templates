<?php

/**
 * Contao Bootstrap templates.
 *
 * @package    contao-bootstrap
 * @subpackage Templates
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2018 netzmacht David Molineus. All rights reserved.
 * @license    https://github.com/contao-bootstrap/templates/blob/master/LICENSE LGPL 3.0-or-later
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Templates\View;

use Contao\FrontendTemplate;
use Contao\Template;
use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Form\FormLayout\HorizontalFormLayout;
use Netzmacht\Contao\FormDesigner\LayoutManager;

/**
 * Class FormView
 */
final class FormRenderer
{
    /**
     * Form layout manager.
     *
     * @var LayoutManager
     */
    private LayoutManager $layoutManager;

    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private Environment $environment;

    /**
     * FormRenderer constructor.
     *
     * @param LayoutManager $layoutManager Form layout manager.
     * @param Environment   $environment   Bootstrap environment.
     */
    public function __construct(LayoutManager $layoutManager, Environment $environment)
    {
        $this->layoutManager = $layoutManager;
        $this->environment   = $environment;
    }

    /**
     * Generate the form view.
     *
     * @param string $templatePrefix The template prefix which gets an _horizontal or _default suffix.
     * @param array  $data           Template data being used in the new template.
     *
     * @return string
     */
    public function render(string $templatePrefix, array $data): string
    {
        $formLayout = $this->layoutManager->getDefaultLayout();

        if ($formLayout instanceof HorizontalFormLayout) {
            $templateName = $templatePrefix . '_horizontal';
        } else {
            $templateName = $templatePrefix . '_default';
        }

        $template = new FrontendTemplate($templateName);
        $template->setData($data);
        $this->prepare($template);

        return $template->parse();
    }

    /**
     * Prepare the template by adding grid related classes.
     *
     * @param Template $template The template.
     *
     * @return void
     */
    public function prepare(Template $template): void
    {
        $formLayout = $this->layoutManager->getDefaultLayout();

        if ($formLayout instanceof HorizontalFormLayout) {
            $template->labelColClass  = $formLayout->getLabelColumnClass();
            $template->colClass       = $formLayout->getColumnClass();
            $template->colOffsetClass = $formLayout->getColumnClass(true);
            $template->rowClass       = $formLayout->getRowClass();
            $template->isHorizontal   = true;
        } else {
            $template->labelColClass  = null;
            $template->colClass       = null;
            $template->colOffsetClass = null;
            $template->rowClass       = null;
            $template->isHorizontal   = false;
        }

        $template->formLayout  = $formLayout;
        $template->buttonClass = $this->getButtonClass();
    }

    /**
     * Get the button class.
     *
     * @return string
     */
    public function getButtonClass(): string
    {
        return 'btn ' . $this->environment->getConfig()->get('form.buttons.submit', 'btn-default');
    }
}
