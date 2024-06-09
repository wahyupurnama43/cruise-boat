<?php

class Env extends Controller
{
     public function setting()
     {
          $this->model('M_Home')->getEnv($_FILES);
     }
}
