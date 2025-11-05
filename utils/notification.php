<?php 

  // success
  function notification_success(string $message): string {
    return notification('Succès',$message,'success');
  }

  // error
  function notification_error(string $message): string {
    return notification('Erreur',$message,'error');
  }

  // warning
  function notification_warning(string $message): string {
    return notification('Avertissement',$message,'warning');
  }

  // info
  function notification_info(string $message): string {
    return notification('Information',$message,'info');
  }


  function notification(string $title, string $message, string $class) {
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

    return '
    <div class="toast-container">
      <div class="toast show '.$color_class.'" style="' . $duree_style . '" role="alert" aria-live="polite" aria-atomic="true">
        <div class="toast-header '.$color_class.'">
          <strong class="mr-auto">' . $title . '</strong>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body '.$color_class.'">
          ' . $message . '
        </div>
        <div class="toast-progress-bar '.$color_class.'"></div>
      </div>
    </div>';
  }

?>