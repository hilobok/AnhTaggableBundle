<?php

namespace Anh\TaggableBundle\Form\Type;

use Symfony\Component\Form\DataTransformerInterface;
use Anh\Taggable\TaggableManager;

class TagsTransformer implements DataTransformerInterface
{
    protected $taggableManager;
    protected $delimiter;

    public function __construct(TaggableManager $taggableManager, $delimiter)
    {
        $this->taggableManager = $taggableManager;
        $this->delimiter = $delimiter;
    }

    public function transform($tags)
    {
        if ($tags === null) {
            return '';
        }

        $result = array();

        foreach ($tags as $tag) {
            $result[] = $tag->getName();
        }

        return implode($this->delimiter, $result);
    }

    public function reverseTransform($value)
    {
        $names = empty($value) ? array() : explode($this->delimiter, $value);

        return $this->taggableManager->loadOrCreateTags($names);
    }
}
