const firebaseConfig = {
  apiKey: "AIzaSyCamucmmYo159c3es0MKcOmayyRXdauIfk",
  authDomain: "shrkospin.firebaseapp.com",
  databaseURL: "https://shrkospin-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "shrkospin",
  storageBucket: "shrkospin.appspot.com",
  messagingSenderId: "297516532383",
  appId: "1:297516532383:web:dad69d5da86f73b96758aa"
};

const app = firebase.initializeApp(firebaseConfig);

const db = firebase.firestore();
// db.setting({ timestampsInSnapshots: true});