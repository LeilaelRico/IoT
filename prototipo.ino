
#include <ESP8266WiFi.h>
#include "FirebaseESP8266.h"
#include <DHT.h>
#include <DHT_U.h>
#include <NTPClient.h>
#include <WiFiUdp.h>

// Credenciales wifi
const char *ssid = "Privada"; // nombre de la red
const char *password = "4422189000";

// Credenciales Proyecto Firebase
const char *FIREBASE_HOST = "https://prototipoiot-1facb-default-rtdb.firebaseio.com";
const char *FIREBASE_AUTH = "v2lDaqT94J49KseYxUsHGZ6XoipgZX7pCEjZtNtx";

// Firebase Data object in the global scope
FirebaseData firebaseData;

bool iterar = true;
DHT dht(D1, DHT11);
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org");

String weekDays[7]={"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"};

String months[12]={"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"};

float temp, hume;
void setup()
{
  Serial.begin(115200);
  Serial.println();

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print(".");
    delay(250);
  }
  Serial.print("\nConectado al Wi-Fi");
  Serial.println();

  Firebase.begin(FIREBASE_HOST, FIREBASE_AUTH);
  Firebase.reconnectWiFi(true);
  dht.begin();
  timeClient.begin();
  timeClient.setTimeOffset(-21600);
}

void loop()
{
  timeClient.update();
  String nodo = "Proyecto1";
  hume = dht.readHumidity();
  temp = dht.readTemperature();
  String formattedTime = timeClient.getFormattedTime(); 
    // push de datos
     Firebase.pushString(firebaseData,  nodo + "/Temperatura", String(temp));
     Firebase.pushString(firebaseData,  nodo + "/Humedad" , String(hume));
     Firebase.pushString(firebaseData, nodo +  "/AT", "Estable");
     Firebase.pushString(firebaseData, nodo +  "/AH", "Estable");
     Firebase.pushString(firebaseData, nodo + "/Tiempo", formattedTime);

     if(temp > 30.1){
        Firebase.pushString(firebaseData, nodo +  "/AT", "¡Me estoy quemando!");
      }
      if(temp < 23.1){
        Firebase.pushString(firebaseData, nodo +  "/AT", "Auxilio me congelo");
      }
      if(hume > 70.1){
        Firebase.pushString(firebaseData, nodo +  "/AH", "Muy mojao");
      }
      if(hume < 39.1){
        Firebase.pushString(firebaseData,nodo + "/AH", "¡Aguaaaaaaaa!");
      }

     delay(10000);
     

} // End Loop
