<?php

require 'Class.Dispatcher.php';
require 'Class.Controller.php';
require 'Class.ModelArray.php';
require 'Class.Session.php';
require 'Metier/Banque.php';
require 'Metier/ComptaNat.php';
require 'Metier/Entreprise.php';
require 'Metier/ListAgents.php';
require 'Metier/ListOperations.php';
require 'Metier/Operation.php';
require 'Framework.php';

class App
{
    public function run()
    {
        new Dispatcher();

    }

}