<?php 

  function toast(string $title, string $message, string $class): string {
    $color = '';
    
    switch ($class) {
      case 'success':
        $color = '#20c997';
        break;
      case 'error':
        $color = '#dc3545';
        break;
      default:
        $color = '#0d6efd';
        break;
    }

    return '<div class="position-absolute" aria-live="polite" aria-atomic="true"  style="min-height: min-content; min-width: min-content; top: 1em; right: 1em;">
      <div class="position-absolute top-0 end-0">
        
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header d-flex flex-row justify-content-between">
            <div class="d-flex flex-row gap-2">
              <svg class="bd-placeholder-img rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                <rect fill="'.$color.'" width="100%" height="100%"></rect>
              </svg>
              <strong class="mr-auto">'.$title.'</strong>
            </div>
            <button type="button" class="ml-2 mb-1 close btn" data-dismiss="toast" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="toast-body">
            '.$message.'
          </div>
        </div>

      </div>
    </div>';
  }

?>