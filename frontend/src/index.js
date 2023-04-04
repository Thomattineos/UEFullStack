import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import ShopModal from './components/ShopModal';

function App() {
  return (
    <>
      <Navbar />
      <Routes>
        <Route path="/shops" element={<ShopModal />} />
      </Routes>
    </>
  );
}

ReactDOM.render(
  <BrowserRouter>
    <App />
  </BrowserRouter>,
  document.getElementById('root')
);