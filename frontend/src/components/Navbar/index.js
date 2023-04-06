import React from 'react';
import { Link } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';

function Navbar() {
  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <div className="container-fluid">
        <h3>MyShop</h3>
        <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span className="navbar-toggler-icon"></span>
        </button>
        <div className="collapse navbar-collapse" id="navbarNav">
          <ul className="navbar-nav" style={{ marginLeft: "5%",  }}>
            <li className="nav-item">
              <Link className="nav-link" to="/shops" style={{ textDecoration: 'none' }}>Boutiques</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link" to="/products" style={{ textDecoration: 'none' }}>Produits</Link>
            </li>
            <li className="nav-item">
              <Link className="nav-link" to="/categories" style={{ textDecoration: 'none' }}>Catégories</Link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  );
}

export default Navbar;