<?php
/**
 * Description of Prebacklog_comentario
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_comentario extends AppModel {
    //put your code here
    public $name = 'Prebacklog_comentario';
    public $useTable = 'prebacklog_comentario';
    public $belongsTo = array(
        'Usuario' => array(
            'className' => 'Usuario',
            'foreignKey' => 'usuario_id'
        ),
    );
}
