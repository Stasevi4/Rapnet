<?php namespace Stasevi4\Rapnet;

use GuzzleHttp\Client;

class Rapnet {

    private $client;
    private $ticket = null;

    /**
     * Rapnet constructor.
     *
     * @param $credential = ['Username' => 'user', 'Password' => 'pass'];
     */
    public function __construct($credential)
    {
        $this->client = new Client(['base_uri' => 'https://technet.rapaport.com/', 'verify' => false]);
        $this->getTicket($credential);
    }

    /**
     * Retrieve and set the authentication ticket
     */
    private function getTicket($credential)
    {
          $response = $this->client->Post("HTTP/Authenticate.aspx", ['form_params'=> $credential] );
          $this->ticket = $response->getBody()->read(2048);
	 }

    /**
     * Save diamonds database
     *
     * @return int
     */
    public function saveDiamondsFeed( $filePath = 'db.csv')
    {
        try {
            set_time_limit(0);
            $resource = fopen($filePath, 'w');
            $this->client->Post("HTTP/DLS/GetFile.aspx", ['form_params' => ['ticket' => $this->ticket], 'sink' => $resource]);
            return true;
        }catch (\Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
}

