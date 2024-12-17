
import './App.css'
import React, { useEffect, useState } from "react";
import axios from 'axios';

const API_URL = "http://localhost/DO-TO/src/api.php";
function App() {

  const [usuarios, setUsuarios] = useState([]);
  const [formData, setFormData] = useState({ nombre: "", email: "", telefono: "" });

// consumienso api con axios



  return (
    <>

    </>
  )
}

export default App
