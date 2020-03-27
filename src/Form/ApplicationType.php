<?php 
namespace App\Form;

use Symfony\Component\Form\AbstractType;

 
class ApplicationType extends AbstractType {
    /**
     * Pertmet d'avoir la configuration de base d'un champs
     *
     * @param string $label
     * @param string placeholder
     * @param array $option
     * @return array
     */
    public function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            "label" => $label,
            "attr" => [
                "placeholder" => $placeholder
            ]
        ], $options);
    }
}