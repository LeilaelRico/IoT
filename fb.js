(function() {
    // Set the configuration for your app
    var config = {
        apiKey: "AIzaSyB45tG_rSAFWYLaU907i09JA3rnrcNjnlY",
        authDomain: "prototipoiot-1facb.firebaseapp.com",
        databaseURL: "https://prototipoiot-1facb-default-rtdb.firebaseio.com",
        projectId: "prototipoiot-1facb",
        storageBucket: "prototipoiot-1facb.appspot.com",
        messagingSenderId: "71499728333",
        appId: "1:71499728333:web:85af8b08576bc4deac7084",
        measurementId: "G-9YVP10FFTP"
    };

    firebase.initializeApp(config);

    // Get a reference to the database service
    var database = firebase.database();

    // Get element from the DOM
    const tempElement = document.getElementById('Temperatura');
    const humElement = document.getElementById('Humedad');
    const altemp = document.getElementById("AT");
    const batemp = document.getElementById("BT");
    const alhum = document.getElementById("AH");
    const bahum = document.getElementById("BH");

    // Create temperature database reference
    const tempRef = database.ref('Proyecto1').child('Temperatura');

    // Create humidity database reference
    const humRef = database.ref('Proyecto1').child('Humedad');

    // Ref. a alarmas temperatura
    const altemRef = database.ref('Proyecto1');
    const batemRef = database.ref('Proyecto1');

    // Ref. a alarmas humedad
    const alhumRef = database.ref('Proyecto1');
    const bahumRef = database.ref('Proyecto1');

    // Sync objects changes
    tempRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            var childData = childSnapshot.val();
            console.log("Temperatura: " + childData);
            tempElement.innerText = childData;
        });
    });

    humRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            var childData = childSnapshot.val();
            console.log("Humedad: " + childData);
            humElement.innerText = childData;
        });
    });

    altemRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(ParentSnapshot) {
            var ParentData = ParentSnapshot.val();
            console.log("AT: " + ParentData);
            altemp.innerText = ParentData;
        });
    });

    batemRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            var childData = childSnapshot.val();
            console.log("BT: " + childData);
            batemp.innerText = childData;
        });
    });

    alhumRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            var childData = childSnapshot.val();
            console.log("AH: " + childData);
            alhum.innerText = childData;
        });
    });

    bahumRef.limitToLast(1).on('value', function(snapshot) {
        snapshot.forEach(function(childSnapshot) {
            var childData = childSnapshot.val();
            console.log("BH: " + childData);
            bahum.innerText = childData;
        });
    });

}());