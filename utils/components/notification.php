<?php 

declare(strict_types=1);

/**
 * Classe comportant un ensemble de méthodes afin d'afficher des notifications avec un code couleur spécifique
 */
class Notification {
  
  /**
   * Méthode qui permet l'affichage d'une notification temporaire avec l'effet de style "success"
   * @param string message à afficher
   */
  public static function notification_success(string $message): string {
    return Notification::notification($message,'success');
  }

  /**
   * Méthode qui permet l'affichage d'une notification temporaire avec l'effet de style "error"
   * @param string message à afficher
   */
  public static function notification_error(string $message): string {
    return Notification::notification($message,'error');
  }

  /**
   * Méthode qui permet l'affichage d'une notification temporaire avec l'effet de style "warning"
   * @param string message à afficher
   */
  public static function notification_warning(string $message): string {
    return Notification::notification($message,'warning');
  }

  /**
   * Méthode qui permet l'affichage d'une notification temporaire avec l'effet de style "info"
   * @param string message à afficher
   */
  static function notification_info(string $message): string {
    return Notification::notification($message,'info');
  }

  private static function notification(string $message, string $class) {
    $color_class = '';
    
    switch ($class) {
      case 'success':
        $color_class = 'toast-color-success';
        break;
      case 'error':
        $color_class = 'toast-color-error';
        break;
      case 'warning':
        $color_class = 'toast-color-warning';
        break;
      default:
        $color_class = 'toast-color-info';
        break;
    }

    $duree = 5;
    $duree_style = "animation-duration: {$duree}s;";

    return <<<HTML
    <div class="toast-container">
      <div class="toast show $color_class" style="$duree_style" role="alert" aria-live="polite" aria-atomic="true">
        <div class="toast-body $color_class justify-content-between d-flex">
          $message
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-progress-bar $color_class"></div>
      </div>
    </div>
HTML;
  }

}

?>