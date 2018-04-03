<?php

namespace Drupal\participer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database;
use Drupal\Core\Session\AccountProxy;


/**
 * Class SubmitForm.
 */
class SubmitForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'submit_form';
  }

  	// Verifier que le champs 'choix_participation' contient un enregistrement 
	protected function verifDataBD(){
		// $nid = \Drupal::routeMatch()->getParameter('node')->id();
		$uid = \Drupal::currentUser()->id();

		$connexion= \Drupal::service('database');

		$data = $connexion->select('participer_event_inscription', 'par')
		->fields('par', 
			array('choix_participation'))

			// ->condition('nid', $nid)
 
			->condition('uid', $uid)
			->orderBy('nid', $direction = 'ASC')

		->execute()
		->fetchAll();
		// kint($data);
		// S'il existe des données en base, je retourne le choix posté par l'utilisateur
		if(!empty($data)){
		    return $data[0]->choix_participation; // return un array qui contient la valeur du champs
		}
	}


  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {

    // kint(\Drupal::routeMatch()->getParameter('node')->getEntityTypeId()); // affiche  "node" 

    $nid = \Drupal::routeMatch()->getParameter('node')->id(); // affiche le numero de l'id de l'evenement de l'url en cours
 

    $uid = \Drupal::currentUser()->id();
    $data = $this->verifDataBD(); 
	

    $form['choix_participation'] = array(
       '#type' => 'radios', 
       '#title' => 'Cela vous intéresse',
       '#options' => array(
         	'participe' =>  'Y participer',
         	'participePas'    =>  'Ne pas y participe',
         	'participePeutEtre'  =>  'Peut être y participer',
        ),
    );
	
    // S'il existe des données en base
    if ($data){
    	$form['choix_participation']['#default_value'] = $data; // la valeur est celle existante en BD est cochée par default pour un uid et nid encours
    }


    $form['submit'] = [
      '#type' => 'submit',
      '#value' => ' Validez votre choix',
    ];  

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {


    parent::validateForm($form, $form_state);
   // kint($form_state);exit;

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

			$nid = \Drupal::routeMatch()->getParameter('node')->id();
			$email = \Drupal::currentUser()->getEmail();
			$date = time();
			$node_title = \Drupal::routeMatch()->getParameter('node')->getTitle() ;
			$nom = \Drupal::currentUser()->getAccountName();
			$uid = \Drupal::currentUser()->id();
			$choix_participation = $form_state->getValue('choix_participation');

			$connexion= \Drupal::service('database');
		if(empty($this->verifDataBD())){
				 $this->insertInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date);
				 return drupal_set_message($this->t('Votre inscription a bien été enregistré'),'status');
		}else{
				 $this->updateInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date);
				 return drupal_set_message($this->t('Votre inscription a bien été modifié'),'status');
		}

		// $form_state->setRebuild();


  } // Fin function submit

	public function insertInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date){

		$insert = $connexion->insert('participer_event_inscription')
				->fields(
					array(
						'nid' =>  $nid,
						'node_title'  => $node_title,
						'uid' => $uid,
						'nom' => $nom,
						'email' => $email,
						'choix_participation' => $choix_participation,
						'date' => $date,					
		))
		->execute();
		return true;
	}

	public function updateInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date){

		$update = $connexion->update('participer_event_inscription')
					->fields(
					array(
						'nid' =>  $nid,
						'node_title'  => $node_title,
						'uid' => $uid,
						'nom' => $nom,
						'email' => $email,
						'choix_participation' => $choix_participation,
						'date' =>$date,	

					))
					->condition('nid', $nid)
					->condition('uid', $uid)
					->execute();

		return true;
	}

	// function de calcul du nombre de ligne dans la table "participer_event_inscription". 
	// Si pas de ligne, je fais une insertion en BD, 
	// sinon s'il ya une ligne je fait un update de cette ligne 

} // Fin class





/*	public function verifyInscriptionEvent($connexion, $nid, $node_title, $uid, $nom, $email, $choix_participation, $date){
		$compteur = $connexion->select('participer_event_inscription', 'par')
					->fields('par',
						array('pid', 'nid','uid', 'node_title', 'uid', 'nom', 'email', 'choix_participation', 'date'  )
					)
					
					->countQuery()

					->execute()
					->fetchField();
// kint($compteur); die();
		// Ici je dicide d'enregistrer en BD qu'un seule ligne.
		if (  $compteur ==='1') { // Si il exite une ligne en BD (1 entree), je retounne Vrai pour faire une update, 
			return true;
		}
		return false; // sinon , je retounre faux pour l'enregistrer en BD par la suite 
*/
