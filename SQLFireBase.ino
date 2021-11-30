
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
const char *FIREBASE_HOST = "https://sqlfirebase-e769e-default-rtdb.firebaseio.com";
const char *FIREBASE_AUTH = "oB3eISNouL0KmoWi9vEd8iHOILYZdxXrpgeWmhnc";

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
  String TD = "Temperatura";
  String HD = "Humedad";
  String AT = "AlarmaTemp";
  String AH = "AlarmaHume";
  String TM = "Tiempo";
  String nodo = "Proyecto1";

  int cont = 0;
  int inf = 99999;
  while (cont < inf){
      hume = dht.readHumidity();
      temp = dht.readTemperature();
      String formattedTime = timeClient.getFormattedTime(); 
      String webo = nodo + "/" + String(cont) + "/" + TD;
      String webo2 = nodo + "/" + String(cont) + "/" + HD;
      String webo3 = nodo + "/" + String(cont) + "/" + AT;
      String webo4 = nodo + "/" + String(cont) + "/" + AH;
      String webo5 = nodo + "/" + String(cont) + "/" + TM;
      
        // push de datos
         Firebase.setInt(firebaseData,  webo, temp);
         Firebase.setInt(firebaseData,  webo2, hume);
         Firebase.setString(firebaseData,  webo3, "Estable");
         Firebase.setString(firebaseData,  webo4, "Estable");
         Firebase.setString(firebaseData,  webo5, formattedTime);
    
         if(temp > 30.1){
            Firebase.setString(firebaseData,  webo3, "¡Me estoy quemando!");
          }
          if(temp < 23.1){
            Firebase.setString(firebaseData,  webo3, "Auxilio me congelo");
          }
          if(hume > 70.1){
            Firebase.setString(firebaseData,  webo4, "Muy mojao");
          }
          if(hume < 39.1){
            Firebase.setString(firebaseData,  webo4, "¡Aguaaaaaaaa!");
          }
          
            cont = cont + 1;
            delay(900000);
     
      }
     

} // End Loop
