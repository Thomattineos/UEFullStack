import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import axios from 'axios';

const UpdateProductForm = () => {
const { id } = useParams();
const navigate = useNavigate();
const [name, setName] = useState('');
const [price, setPrice] = useState('');
const [showToast, setShowToast] = useState(false);
const [toastMessage, setToastMessage] = useState("");
const [toastType, setToastType] = useState("");

useEffect(() => {
    const productId = parseInt(id, 10);
    axios.get(`http://localhost:8000/api/products/${productId}`)
        .then(response => {
            const product = response.data;
            setName(product.name);
            setPrice(product.price);
        })
        .catch(error => console.log(error));
}, [id]);

const handleSubmit = e => {
    e.preventDefault();
    const updatedProduct = {
        name,
        price,
    };
    axios.put(`http://localhost:8000/api/products/${id}`, updatedProduct)
        .then((response) => {
            if (response.status === 200) {
                setToastMessage("Produit modifié avec succès");
                setToastType("bg-success text-white");
                setShowToast(true);

            } else {
                setToastMessage("Impossible de modifier le produit");
                setToastType("bg-danger text-white");
                setShowToast(true);
            }
        })
        .catch((error) => {
            setToastMessage("Impossible de modifier le produit");
            setToastType("bg-danger text-white");
            setShowToast(true);
        });
};

const handleBack = () => {
    navigate('/products');
};

return (
    <>
        <div style={{ margin: '5%', padding: '2%', border: '1px solid', borderRadius: '15px' }}>
            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Nom du produit:</label>
                    <input
                        type="text"
                        id="name"
                        className="form-control"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                    />
                </div>
                <div className="mb-3">
                    <label htmlFor="price" className="form-label">Prix :</label>
                    <input
                        type="number"
                        id="price"
                        className="form-control"
                        value={price}
                        onChange={(e) => setPrice(e.target.value)}
                    />
                </div>
                <button type="submit" className="btn btn-primary">Modifier le produit</button>
            </form>

            {showToast && (
                <div
                    className={`toast show position-fixed bottom-0 end-0 m-3 ${toastType}`}
                    role="alert"
                    aria-live="assertive"
                    aria-atomic="true"
                >
                    <div className="toast-header">
                        <strong className="me-auto">Message</strong>
                        <button
                            type="button"
                            className="btn-close"
                            data-bs-dismiss="toast"
                            aria-label="Close"
                            onClick={() => setShowToast(false)}
                        ></button>
                    </div>
                    <div className="toast-body">{toastMessage}</div>
                    <div className="toast-footer">
                    </div>
                </div>
            )}
        </div>
        <div className="d-flex justify-content-center">
            <button className="btn btn-primary mt-3" onClick={handleBack}>
                Retour
                </button>
            </div>
        </>
    );
};

export default UpdateProductForm;