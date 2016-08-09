<?php
declare(strict_types=1);

namespace Core\Form;

use Zend\Form\ElementInterface;
use Zend\Form\Form;
use Zend\Http\Header\Referer;
use Zend\Http\Request;
use Zend\InputFilter\InputProviderInterface;

/**
 * Abstract form to handle easier form creation
 *
 * @package Base\Form
 */
class AbstractForm extends Form
{
    /**
     * New mode
     */
    const MODE_NEW = 'new';

    /**
     * Edit mode
     */
    const MODE_EDIT = 'edit';

    /**
     * List of available modes
     */
    const MODE_LIST = [
        self::MODE_NEW,
        self::MODE_EDIT
    ];

    /**
     * Send method post
     */
    const METHOD_POST = 'post';

    /**
     * Send method get
     */
    const METHOD_GET = 'get';

    /**
     * List of available methods
     */
    const METHOD_LIST = [
        self::METHOD_GET,
        self::METHOD_POST
    ];

    /**
     * Mode of operation
     *
     * @var string
     */
    protected $mode;

    /**
     * Request object
     *
     * @var \Zend\Http\Request
     */
    protected $request;

    /**
     * Quickly add an element and return it
     *
     * @param string $name Name of element
     * @param string $type Element type
     * @param string $label Label to use for element rendering
     * @param array $inputFilterParameters additional parameters for the input filter
     * @return \Zend\Form\ElementInterface the created element
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     */
    public function addElement(string $name, string $type, string $label = null, array $inputFilterParameters = []): ElementInterface
    {
        if ($label === null) {
            $label = $name;
        }

        $this->add([
            'name' => $name,
            'type'  => $type,
            'options' => [
                'label' => $label,
            ],
            'attributes' => [
                'id' => $name,
            ],
        ]);

        $element = $this->get($name);

        if ($element instanceof InputProviderInterface) {
            $inputFilterParameters = array_merge($element->getInputSpecification(), $inputFilterParameters);
        }

        if (count($inputFilterParameters) > 0) {
            $inputFilterParameters['name'] = $name;
            $this->getInputFilter()->add($inputFilterParameters);
        }

        return $this->get($name);
    }

    /**
     * Removes element and filters
     *
     * @param string $name Element name
     * @return void
     */
    public function removeElement(string $name)
    {
        $this->remove($name);
        $this->getInputFilter()->remove($name);
    }

    /**
     * Sets mode
     *
     * @param string|null $mode Mode of operation (see MODE_* consts)
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setMode(string $mode = null)
    {
        if ($mode !== null && !in_array($mode, self::MODE_LIST, true)) {
            throw new \InvalidArgumentException('Unknown mode: ' . $mode);
        }

        $this->mode = $mode;
    }

    /**
     * Returns mode or null (see MODE_* consts)
     *
     * @return string|null
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Sets the method for the form
     *
     * @param string $method Send method
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setMethod(string $method)
    {
        if (!in_array($method, self::METHOD_LIST, true)) {
            throw new \InvalidArgumentException('Unknown method: ' . $method);
        }

        $this->setAttribute('method', $method);
    }

    /**
     * Returns the method
     *
     * @return string|null
     */
    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    /**
     * Sets the referer
     *
     * @param \Zend\Http\Request $request
     * @return void
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     */
    public function setReferer(Request $request)
    {
        $referer = $request->getHeader('Referer');

        if ($referer instanceof Referer) {
            $referer = $referer->uri();
            if ($referer === $request->getUriString()) {
                $referer = '';
            }
        } else {
            $referer = '';
        }

        $this->addElement('referer', 'hidden', '', [
            'required' => false,
        ])->setValue($referer);
    }

    /**
     * Sets the request
     *
     * @param \Zend\Http\Request $request Request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns the request
     *
     * @return \Zend\Http\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}
