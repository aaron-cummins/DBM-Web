<?php

/**
 * Description of MedicionComponente
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class MedicionComponente extends AppModel {
    //put your code here
    public $name = 'MedicionComponente';
    public $useTable = 'medicion_componente';
    var $displayField = "nombre";
}
