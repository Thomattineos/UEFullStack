import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Table, Dropdown, Button } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';

function ShopModal() {
  const [shops, setShops] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [sort, setSort] = useState('name');

  const navigate = useNavigate();

  const handleAddShop = () => {
    navigate('/createShop');
  };

  const handlePrevPage = () => {
    if (currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };

  const handleNextPage = () => {
    if (currentPage < totalPages) {
      setCurrentPage(currentPage + 1);
    }
  };

  const handleSortChange = ($key) => {
    console.log(`/api/shops?sort=${$key}`);
    setSort($key);
    axios
      .get(`http://localhost:8080/api/shops?sortBy=${$key}`)
      .then((response) => {
        fetch(`http://localhost:8080/api/shops?page=${currentPage}&limit=8&sortBy=${$key}`)
        .then(response => response.json())
        .then(data => {
          if (data.shops.length === 0) {
            setCurrentPage(prev => prev - 1);
            setTotalPages(prev => prev - 1);
          }
          const processedData = data.shops.map(shop => ({
            ...shop,
            openingHours: new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            closingHours: new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            creationDate: new Date(shop.creationDate.date).toLocaleTimeString([], { year: 'numeric', month: '2-digit', day: '2-digit' }).substring(0, 10)
          }));
          setShops(processedData);
          setTotalPages(data.pagination.totalPages)});
        }).catch((error) => {
        console.log(error);
      });
  };

  useEffect(() => {
    fetch(`http://localhost:8080/api/shops?page=${currentPage}&limit=8${sort ? `&sortBy=${sort}` : ''}`)
      .then(response => response.json())
      .then(data => {
        const processedData = data.shops.map(shop => ({
          ...shop,
          openingHours: new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          closingHours: new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          creationDate: new Date(shop.creationDate.date).toLocaleTimeString([], { year: 'numeric', month: '2-digit', day: '2-digit' }).substring(0, 10),
        }));
        setShops(processedData);
        setTotalPages(data.pagination.totalPages);
      });
  }, [currentPage, sort]);

  const updateShop = (id) => {
    navigate(`/${id}/updateShop`);
  }

  const deleteShop = (id) => {
    fetch(`http://localhost:8080/api/shops/${id}`, {
      method: 'DELETE',
    }).then(() => {
      fetch(`http://localhost:8080/api/shops?page=${currentPage}&limit=8`)
        .then(response => response.json())
        .then(data => {
          if (data.shops.length === 0) {
            setCurrentPage(prev => prev - 1);
            setTotalPages(prev => prev - 1);
          }
          const processedData = data.shops.map(shop => ({
            ...shop,
            openingHours: new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            closingHours: new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            creationDate: new Date(shop.creationDate.date).toLocaleTimeString([], { year: 'numeric', month: '2-digit', day: '2-digit' }).substring(0, 10)
          }));
          setShops(processedData);
          setTotalPages(data.pagination.totalPages);
        });
    }).catch(error => console.error(error));
  }

  return (
    <>
      <h1 style={{ textAlign: "center", marginTop: "4%" }}>Liste des boutiques</h1>
      <div style={{ display: "flex", marginLeft: "5%" }}>
        <Dropdown>
          <Dropdown.Toggle variant="primary" id="dropdown-sort">Trier par : {
            sort === "name" ? "Nom" :
            sort === "creationDate" ? "Date de création" :
            sort === "numProducts" ? "Nombre de produits" : "Aucun"
          }
          </Dropdown.Toggle>

          <Dropdown.Menu>
            <Dropdown.Item eventKey="name" onClick={() => handleSortChange("name")}>
              Nom
            </Dropdown.Item>
            <Dropdown.Item eventKey="creationDate" onClick={() => handleSortChange("creationDate")}>
              Date de création
            </Dropdown.Item>
            <Dropdown.Item eventKey="product_count" onClick={() => handleSortChange("numProducts")}>
              Nombre de produits
            </Dropdown.Item>
          </Dropdown.Menu>
        </Dropdown>
      </div>
      <div style={{ paddingTop: '4%', paddingRight: '5%', paddingLeft: '5%' }}>
        <Table striped bordered hover style={{ textAlign: 'center' }}>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Horaires d'ouverture</th>
              <th>Horaires de fermeture</th>
              <th>Congé</th>
              <th>Date de création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {shops.map((shop) => (
              <tr key={shop.id}>
                <td>{shop.name}</td>
                <td>{shop.openingHours.toString()}</td>
                <td>{shop.closingHours.toString()}</td>
                <td>{shop.available ? 'Oui' : 'Non'}</td>
                <td>{shop.creationDate.toString()}</td>
                <td>
                  <button className="btn btn-primary" style={{ marginRight: '5%' }} onClick={() => updateShop(shop.id)}>Modifier</button>
                  <button className="btn btn-danger" onClick={() => deleteShop(shop.id)}>Supprimer</button>
                </td>
              </tr>
            ))}
          </tbody>
        </Table>
      </div>
      <div className="d-flex justify-content-center">
        <button className="btn btn-primary mr-2" onClick={handlePrevPage}>Précédent</button>
        <button className="btn btn-primary mr-2" disabled>{currentPage} / {totalPages}</button>
        <button className="btn btn-primary" onClick={handleNextPage}>Suivant</button>
      </div>
      <div className="d-flex justify-content-center">
        <button className="btn btn-primary mt-3" onClick={handleAddShop}>
          Ajouter une boutique
        </button>
      </div>
    </>
  );
}

export default ShopModal;