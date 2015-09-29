<?php

namespace Anh\TaggableBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Anh\Taggable\TaggableManager;
use Anh\TaggableBundle\Form\Type\TagsTransformer;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;

class TagsType extends AbstractType
{
    /**
     * Holds TaggableManager
     */
    protected $taggableManager;

    /**
     * Holds router
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Anh\Taggable\TaggableManager $taggableManager
     */
    public function __construct(TaggableManager $taggableManager, Router $router)
    {
        $this->taggableManager = $taggableManager;
        $this->router = $router;
    }

    /**
     * Resolves tag-it options.
     * See {@link https://github.com/aehlke/tag-it}
     *
     * @param FormInterface $form
     */
    protected function getTagitOptions(FormInterface $form)
    {
        $resolver = new OptionsResolver();
        $method = is_callable(array($resolver, 'setDefined')) ? 'setDefined' : 'setOptional';
+
+       $resolver->$method(array(
            'availableTags',
            'autocomplete',
            'showAutocompleteOnFocus',
            'removeConfirmation',
            'caseSensitive',
            'allowDuplicates',
            'allowSpaces',
            'readOnly',
            'tagLimit',
            'singleField',
            'singleFieldDelimiter',
            'singleFieldNode',
            'tabIndex',
            'placeholderText'
        ));

        $resolver->setAllowedTypes('autocomplete', 'array'); // http://api.jqueryui.com/autocomplete/

        $resolver->setDefaults(array(
            'allowSpaces' => true,
            'removeConfirmation' => true,
            'singleFieldDelimiter' => ',',
            'autocomplete' => array()
        ));

        return $resolver->resolve(
            $form->getConfig()->getOption('tagit', array())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tagitOptions = $this->getTagitOptions($builder->getForm());
        $builder->addModelTransformer(
            new TagsTransformer($this->taggableManager, $tagitOptions['singleFieldDelimiter'])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $tagitOptions = $this->getTagitOptions($form);
        $autocomplete = $form->getConfig()->getOption('autocomplete');

        switch ($autocomplete) {
            case 'dynamic':
                $tagitOptions['autocomplete'] = $tagitOptions['autocomplete'] + array(
                    'source' => $this->router->generate('anh_taggable_search')
                );
                break;

            case 'static':
                $tagitOptions['availableTags'] = $this->taggableManager
                    ->getTagRepository()
                        ->search('%', false, 'name', 100)
                ;
                break;

            case 'custom':
                break;
        }

        $tagitOptions['fieldName'] = $view->vars['full_name'];
        $tagitOptions['singleField'] = true;

        $view->vars['attr']['data-tagit'] = json_encode($tagitOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setOptional(array(
            'autocomplete', // type of autocomplete
            'tagit' // tag-it related options
        ));
        $resolver->setAllowedTypes(array(
            'tagit' => 'array'
        ));
        /**
         * dynamic - tags fetched dynamicaly via ajax (you may set custom url in ['tag-it']['autocomplete']['source']) (limited to 100 tags)
         * static  - all tags passed via attribute (limited to 100 tags)
         * custom  - no tags will be provided from db (custom list of tags may be passed by setting ['tag-it']['availableTags'])
         */
        $resolver->setAllowedValues('autocomplete', array(
            'dynamic', 'static', 'custom'
            ));
        $resolver->setDefaults(array(
            'autocomplete' => 'dynamic'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tags';
    }
}
