<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prebacklog_envioEmail
 *
 * @author AZUNIGA
 */
App::uses('AppModel', 'Model');
class Prebacklog_envioEmail extends AppModel {
    //put your code here
    public $name = 'Prebacklog_envioEmail';
    public $useTable = 'prebacklog_envioemail';
    
    /*public $belongsTo = array(
        'Prebacklog_envioEmailFaena' => array(
            'className' => 'Prebacklog_envioEmailFaena',
            'foreignKey' => 'id'
        ),
    );*/
    
}
