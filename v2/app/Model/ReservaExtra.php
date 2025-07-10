<?php
class ReservaExtra extends AppModel {
    public $belongsTo = array('Extra','ExtraVariable','Usuario');
    var $actsAs = array('Containable');
}
?>
