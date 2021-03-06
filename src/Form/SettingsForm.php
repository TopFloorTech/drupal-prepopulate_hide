<?php

namespace Drupal\prepopulate_hide\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'prepopulate_hide_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('prepopulate_hide.settings')
      ->set('included_forms', $form_state->getValue('included_forms'))
      ->set('excluded_forms', $form_state->getValue('excluded_forms'))
      ->set('included_fields', $form_state->getValue('included_fields'))
      ->set('prepopulate_from_current_page', $form_state->getValue('prepopulate_from_current_page'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['prepopulate_hide.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('prepopulate_hide.settings');

    $form['forms'] = [
      '#type' => 'details',
      '#open' => TRUE,
      '#title' => $this->t('Forms'),
    ];

    $form['forms']['included_forms'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Included forms'),
      '#description' => $this->t('A list of form IDs to match, one per line. You may also use regex by starting and ending the line with "/"'),
      '#default_value' => $config->get('included_forms'),
    ];

    $form['forms']['excluded_forms'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Excluded forms'),
      '#description' => $this->t('A list of form IDs to exclude from the matches, one per line. You may also use regex by starting and ending the line with "/"'),
      '#default_value' => $config->get('excluded_forms'),
    ];

    $form['fields'] = [
      '#type' => 'details',
      '#open' => FALSE,
      '#title' => $this->t('Fields'),
    ];

    $form['fields']['included_fields'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Included fields'),
      '#description' => $this->t('A list of field machine names to include in the matched forms.'),
      '#default_value' => $config->get('included_fields'),
    ];

    $form['prepopulate'] = [
      '#type' => 'details',
      '#open' => FALSE,
      '#title' => $this->t('Prepopulate'),
    ];

    $form['prepopulate']['prepopulate_from_current_page'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Prepopulate from current page'),
      '#description' => $this->t('Specify a list of form IDs and fields, pipe-separated, which should be pre-populated from the current request entity. These fields can then be hidden with the settings above.'),
      '#default_value' => $config->get('prepopulate_from_current_page'),
    ];

    return parent::buildForm($form, $form_state);
  }

}
