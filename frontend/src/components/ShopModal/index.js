import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Table } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

function ShopModal() {
  const [shops, setShops] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

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

  useEffect(() => {
    fetch(`http://localhost:8080/api/shops?page=${currentPage}&limit=8`)
      .then(response => response.json())
      .then(data => {
        const processedData = data.shops.map(shop => ({
          ...shop,
          openingHours: new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          closingHours: new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        }));
        setShops(processedData);
        setTotalPages(data.pagination.totalPages);
      });
  }, [currentPage]);

  const updateShop = (id) => {
    console.log("id=" + id);
    navigate(`/${id}/updateShop`);
  }

  const deleteShop = (id) => {
    fetch(`http://localhost:8080/api/shops/${id}`, {
      method: 'DELETE',
    }).then(() => {
      fetch(`http://localhost:8080/api/shops?page=${currentPage}&limit=8`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if(data.shops.length === 0) {
                    setCurrentPage(prev => prev - 1 );
                    setTotalPages(prev => prev - 1);
                }
                const processedData = data.shops.map(shop => ({
                  ...shop,
                  openingHours: new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                  closingHours: new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                }));
                setShops(processedData);
                setTotalPages(data.pagination.totalPages);
            });
    }).catch(error => console.error(error));
  }

  return (
    <>
      <h1 style={{textAlign: "center", marginTop: "4%"}}>Liste des boutiques</h1>
      <div style={{ paddingTop: '4%', paddingRight: '5%', paddingLeft: '5%' }}>
        <Table striped bordered hover style={{ textAlign: 'center' }}>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Horaires d'ouverture</th>
              <th>Horaires de fermeture</th>
              <th>Congé</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {shops.map((shop) => (
              <tr key={shop.id}>
                <td width={'15%'}>{shop.name}</td>
                <td>{shop.openingHours.toString()}</td>
                <td>{shop.closingHours.toString()}</td>
                <td>{shop.available ? 'Oui' : 'Non'}</td>
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