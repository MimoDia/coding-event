<?php

namespace Drupal\audio_embed_field;

/**
 * Providers an interface for embed providers.
 */
interface ProviderPluginInterface {

  /**
   * Check if the plugin is applicable to the user input.
   *
   * @param string $input
   *   User input to check if it's a URL for the given provider.
   *
   * @return bool
   *   If the plugin works for the given URL.
   */
  public static function isApplicable($input);

  /**
   * Render a thumbnail.
   *
   * @param string $image_style
   *   The quality of the thumbnail to render.
   * @param string $link_url
   *   Where the thumbnail should be linked to.
   *
   * @return array
   *   A renderable array of a thumbnail.
   */
  public function renderThumbnail($image_style, $link_url);

  /**
   * Get the URL of the remote thumbnail.
   *
   * This is used to download the remote thumbnail and place it on the local
   * file system so that it can be rendered with image styles. This is only
   * called if no existing file is found for the thumbnail and should not be
   * called unnecessarily, as it might query APIs for audio thumbnail
   * information.
   *
   * @return string
   *   The URL to the remote thumbnail file.
   */
  public function getRemoteThumbnailUrl();

  /**
   * Get the URL to the local thumbnail.
   *
   * This method does not gartunee that the file will exist, only that it will
   * be the location of the thumbnail after the download thumbnail method has
   * been called.
   *
   * @return string
   *   The URI for the local thumbnail.
   */
  public function getLocalThumbnailUri();

  /**
   * Download the remote thumbnail URL to the local thumbnail URI.
   */
  public function downloadThumbnail();

  /**
   * Render embed code.
   *
   * @param string $width
   *   The width of the audio player.
   * @param string $height
   *   The height of the audio player.
   * @param bool $autoplay
   *   If the audio should autoplay.
   *
   * @return mixed
   *   A renderable array of the embed code.
   */
  public function renderEmbedCode($width, $height, $autoplay);

  /**
   * Get the ID of the audio from user input.
   *
   * @param string $input
   *   Input a user would enter into a audio field.
   *
   * @return string
   *   The ID in whatever format makes sense for the provider.
   */
  public static function getIdFromInput($input);

  /**
   * Get the name of the audio.
   *
   * @return string
   *   A name to represent the audio for the given plugin.
   */
  public function getName();

}
