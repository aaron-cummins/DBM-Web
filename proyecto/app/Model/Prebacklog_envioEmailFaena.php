<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prebacklog_envioEmailFaena
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_envioEmailFaena extends AppModel {
    //put your code here
    
    public $name = 'Prebacklog_envioEmailFaena';
    public $useTable = 'prebacklog_envioemail_faena';
    public $belongsTo = array(
        'Faena' => array(
            'className' => 'Faena',
            'foreignKey' => 'faena_id'
        ),
        'Prebacklog_envioEmail' => array(
            'className' => 'Prebacklog_envioEmail',
            'foreignKey' => 'envioemail_id'
        ),
    );
}
