<?php

namespace Drupal\audio_embed_field\Plugin\audio_embed_field\Provider;

use Drupal\audio_embed_field\ProviderPluginBase;

/**
 * A custom URL provider plugin.
 *
 * @AudioEmbedProvider(
 *   id = "custom_url",
 *   title = @Translation("Custom URL")
 * )
 */
class CustomUrl extends ProviderPluginBase {

  /**
   * {@inheritdoc}
   */
  public function renderEmbedCode($width, $height, $autoplay) {
    $embed_code = [
      '#type' => 'audio_embed_html5',
      '#provider' => 'custom_url',
      '#url' => $this->getInput(),
    ];

    return $embed_code;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteThumbnailUrl() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function getIdFromInput($input) {

    if (strpos($input, 'http://') !== false || strpos($input, 'https://') !== false) {

      if (strpos($input, '.mp3') !== false) {
        return 'mp3';
      }

      if (strpos($input, '.mp4') !== false) {
        return 'mp4';
      }

      if (strpos($input, '.m4a') !== false) {
        return 'm4a';
      }

      if (strpos($input, '.aac') !== false) {
        return 'aac';
      }

      if (strpos($input, '.ogg') !== false) {
        return 'ogg';
      }

      if (strpos($input, '.oga') !== false) {
        return 'oga';
      }

      if (strpos($input, '.wav') !== false) {
        return 'wav';
      }

    }
    return NULL;
  }

}
