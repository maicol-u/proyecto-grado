<?php

namespace App\Console\Commands;

use App\Services\LecturaService;
use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttListener extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:mqtt';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $service = app(LecturaService::class);
    $server   = env('MQTT_HOST');
    $port     = env('MQTT_PORT', 1883);
    $clientId = 'laravel_mqtt_' . uniqid();

    $mqtt = new MqttClient($server, $port, $clientId);

    $settings = (new ConnectionSettings)
      ->setUsername(env('MQTT_USERNAME'))
      ->setPassword(env('MQTT_PASSWORD'))
      ->setKeepAliveInterval(60);

    $mqtt->connect($settings, true);

    $this->info('Conectado a MQTT');

    $mqtt->subscribe('invernadero/sensor/humedad/+/', function ($topic, $message) use ($service) {

      $this->info("Datos recibidos: " . $message . ' Topic: ' . $topic);
      $data = json_decode($message, true);

      if (!is_array($data)) {
        $this->info("Datos no guardados");
        return;
      }

      $service->procesarLectura($data);

      $this->info("Datos guardados");
    }, 1);

    $mqtt->loop(true);
  }
}
