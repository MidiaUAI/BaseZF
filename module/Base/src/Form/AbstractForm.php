<?php

namespace Base\Form;

use Laminas\Form\Form;

/**
 * Description of AbstrctForm
 *
 * @author Thalles Ferreira
 */
// ->setOptions(['disable_inarray_validator' => true])
// Quando se quer passar um Select vazio para preencher com ajax por exemplo.

class AbstractForm extends Form
{

    public function __construct($name = null, $options = []) {
        parent::__construct($name, $options);
        $this->setAttribute('autocomplete', 'off');
    }

    /**
     * @return Doctrine EntityManager
     */
    public function getEm()
    {
        return \Base\Plugin\EntityManager::getEm();
    }

    /**
     * @param string $entityName
     * @return Repository Doctrine
     */
    protected function getRepository($entityName) {
        if (! strpos($entityName, 'Entity')) {
            return $this->getEm()->getRepository(trim('Base\Entity\\' . $entityName));
        }
        return $this->getEm()->getRepository(trim($entityName));
    }

    /**
     * @example $this->getOpcoes('programa', ['nome','id'], [], ['nome'=>'asc']);
     * @param string $entityName nome da entidade onde serão recuperados os dados
     * @param array $campos nome do atributo que será exibido nas tags option
     * @param array $criteria array('nome-do-campo' => 'valor-a-buscar')
     * @param array $order por onde ordenar array('nome-do-campo' => 'asc')
     * @return array options, para Checkbox, selectBox ou Radio list
     */
    public function getOpcoes($entityName, array $campos, $criteria = [], $order = []) {
        $entities = $this->getRepository(ucfirst($entityName))->findBy($criteria, $order);

        if (count($entities) > 0) {
            //Monta os getters
            $getCampos = "";
            $options['-1'] = '...';
            foreach ($campos as $campo) {
                $campo = ucfirst($campo);
                if (ucfirst(end($campos)) == $campo) {
                    $getCampos .= ' $option->get' . $campo . '();';
                } else {
                    $getCampos .= '$option->get' . $campo . '()." - ". ';
                }
            }
            //Monta o array de options que será retornado
            foreach ($entities as $option) {
                $options[$option->getId()] = eval("return {$getCampos}");
            }
        } else {
            $options['-1'] = 'Não há registros de ' . ucfirst($entityName);
        }
        return $options;
    }

    /**
     * @example $this->getOpcoesComboBox('programa', ['nome','id'], [], ['nome'=>'asc']);
     * @param string $entityName nome da entidade onde serão recuperados os dados
     * @param array $campos nome do atributo que será exibido nas tags option
     * @param array $criteria ['nome-do-campo' => 'valor-a-buscar']
     * @param array $order por onde ordenar ['nome-do-campo' => 'asc']
     * @return array options, para selectBox
     */
    public function getOpcoesComboBox($entityName, array $campos, $criteria = [], $order = [])
    {
        $entityName = ucfirst($entityName);
        $entities = $this->getRepository($entityName)->findBy($criteria, $order);

        if (count($entities) > 0) {
            //Monta os getters
            $getCampos = "";
            foreach ($campos as $campo) {
                $campo = ucfirst($campo);

                if (ucfirst(end($campos)) == $campo) {
                    $getCampos .= ' $option->get' . $campo . '();';
                } else {
                    $getCampos .= '$option->get' . $campo . '()." - ". ';
                }
            }

            //Monta o array de options que será retornado
            $options[0] = '---';
            foreach ($entities as $option) {
                $options[$option->getId()] = eval("return {$getCampos}");
            }
        } else {
            $options[0] = 'Não há ' . $entityName . ' Cadastrado';
        }
        return $options;
    }

    public function getOpcoesEnum($tabela, $campo)
    {

        $select = "SHOW COLUMNS FROM {$tabela} WHERE COLUMNS.FIELD = '{$campo}' ";
        $result = $this->getEm()->getConnection()->executeQuery($select)->fetch();

        $substituir = ["enum", "'", "(", ")"];
        $por = ['', '', '', ''];

        $result = explode(',', str_replace($substituir, $por, $result['Type']));
        sort($result);

        foreach ($result as $option) {
            $options[$option] = $option;
        }

        return $options;
    }

    public function getOpcoesComboBoxEnum($tabela, $campo)
    {
        return array_merge([0 => 'Escolha ...'], $this->getOpcoesEnum($tabela, $campo));
    }
}
