<?php

class HomeController extends Controller
{
    public function __construct()
    {
        // Only authenticated users can access this controller
        $this->setPermissions(['auth']);
    }

    public function index()
    {
        // Default home message
        $this->printJson(['success' => true, 'response' => 'Hello World']);
    }
}
