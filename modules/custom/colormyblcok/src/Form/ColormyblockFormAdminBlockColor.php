<?php

namespace Drupal\colormyblock\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class colormyblockFormAdminBlockColor.
 */
class ColormyblockFormAdminBlockColor extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'colormyblock.formadminblockcolor.config', 
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'colormyblock_form_admin_block_color';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('colormyblock.formadminblockcolor.config');

   // Obntenir la valeur des differentes couleurs
    $valeur = $form_state->getValue('color');

    $form =[]; // pas obligatoire ici: $form =[]

    $form['color'] = array(
           '#type' => 'select',
           '#title' => t('color the block in an region page'),
           '#description' => t('chose your color'),
           '#options' => array(
             'red' => $this->t('red'),
             'green' => $this->t('green'),
            'yellow' => $this->t('yellow'),
            '#default_value'=> $valeur,
    ));

    $form['validate']= array(
        '#type' => 'submit',
        '#value' => $this->t('Save configuration for this custum AdminFormBlockColor'),
        '#button_type' => 'primary',
    );  
   //  return parent::buildForm($form, $form_state); // renvoie "save configuration" dans un boutton submit
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    // Obntenir la valeur des differentes couleurs
    $valeur = $form_state->getValue('color'); 
    // on veut enregister la valeur de la couleur et conserver la valeur de la couleur choisie de façon pérrene
    $this->config('colormyblock.formadminblockcolor.config')->set('color', $valeur)->save();



      drupal_set_message($this->t('The configuration options have been saved.')); 

      parent::submitForm($form, $form_state);
  }

}
