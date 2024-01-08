<?php
/**
 * Description of Prebacklog_archivo
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_archivo extends AppModel  {
    //put your code here
    public $name = 'Prebacklog_archivo';
    public $useTable = 'prebacklog_archivo';
    public $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        )
    );
}
