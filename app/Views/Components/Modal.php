<?php 

namespace App\Views\Components;

/**
 * Classe comportant un ensemble de méthodes afin d'afficher des notifications avec un code couleur spécifique
 */
class Modal {
  
  /**
   * Méthode qui permet l'affichage d'une notification temporaire avec l'effet de style "success"
   * @param string message à afficher
   */
  public static function modal_deletion(string $id, string $redirect, string $message): string {
    
    return <<<HTML
    <div id="$id" class="modal fade" tabindex="-1" aria-labelledby="$id" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold text-danger">Confirmer la suppression</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column gap-3">
                    <span>$message</span>
                    <span class="fw-bold">Cette action est irréversible.</span>
                </div>
                <div class="modal-footer border-0">
                    <a href="$redirect">
                        <button type="button" class="btn btn-danger">Confirmer</button>
                    </a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </div>
    </div>
HTML;
  }


}

?>