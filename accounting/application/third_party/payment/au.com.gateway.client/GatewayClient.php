<?php
 
class GatewayClient {

    private $payment;
    private $vault;
    private $report;

    public function __construct(ClientConfig $config) {
        
        $this->payment = new Payment($config);
        $this->vault = new Vault($config);
        $this->report = new Report($config);
    }

    public function payment() {
        return $this->payment;
    }

    public function vault() {
        
        return $this->vault;
    }

    public function report() {
        return $this->report;
    }

}
