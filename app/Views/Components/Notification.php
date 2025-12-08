<?php 

namespace App\Views\Components;

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
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi-check-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>';
        break;
      case 'error':
        $color_class = 'toast-color-error';
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi-exclamation-triangle-fill" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/></svg>';
        break;
      case 'warning':
        $color_class = 'toast-color-warning';
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi-exclamation-triangle-fill" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/></svg>';
        break;
      default:
        $color_class = 'toast-color-info';
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi-info-circle-fill" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/></svg>';
        break;
    }

    $duree = 5;
    $duree_style = "animation-duration: {$duree}s;";

    return <<<HTML
    <div class="toast-container">
      <div class="toast show $color_class" style="$duree_style" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body $color_class justify-content-between d-flex">
          <div class="d-flex flex-row gap-2 align-items-center">
            $icon  
            $message
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-progress-bar $color_class"></div>
      </div>
    </div>
HTML;
  }

}

?>