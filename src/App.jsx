
import './App.css'
import React, { useEffect, useState } from "react";
import axios from 'axios';

const API_URL = "http://localhost/DO-TO/src/api.php";

function App() {

  const [taks, setTaks] = useState([]);
  const [formData, setFormData] = useState({ nameTaks:"", descriptionTack: "", date: ""});

// consumienso api con axios
  const handleSubmit = async () => {
    const res = await axios.get(API_URL);
    setTaks(res.data);
  }



  return (
    
    <>
  
     <div className="Container_app">
      <h1>TO-DO LiKS</h1>
      
      <form onSubmit={handleSubmit}>
        <input
          required
          type="text"
          placeholder="Name taks"
          value={formData.nombre}
          onChange={(e) => setFormData({ ...formData, nameTaks: e.target.value })}
        />
        <input required
          type="text"
          placeholder="description"
          value={formData.descriptionTack}
          onChange={(e) => setFormData({ ...formData, descriptionTack: e.target.value })}
        />
        <button type="submit">Agregar</button>
      </form>

      <h2>Taks</h2>
      <ul>
        {taks.map((tak) => (
          <li key={tak.id}>
            {tak.nombre} - {tak.email} - {tak.telefono}
          </li>
        ))}
      </ul>
    </div>

    </>
  )
}

export default App
