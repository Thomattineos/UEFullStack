import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import axios from 'axios';

const UpdateShopForm = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [name, setName] = useState('');
    const [openingHours, setOpeningHours] = useState('');
    const [closingHours, setClosingHours] = useState('');
    const [available, setAvailable] = useState(false);
    const [showToast, setShowToast] = useState(false);
    const [toastMessage, setToastMessage] = useState("");
    const [toastType, setToastType] = useState("");

    useEffect(() => {
        const shopId = parseInt(id, 10);
        axios.get(`http://localhost:8000/api/shops/${shopId}`)
            .then(response => {
                const shop = response.data;
                setName(shop.name);
                setOpeningHours(new Date(shop.openingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
                setClosingHours(new Date(shop.closingHours.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
                setAvailable(shop.available);
            })
            .catch(error => console.log(error));
    }, [id]);

    const handleSubmit = e => {
        e.preventDefault();
        const updatedShop = {
            name,
            openingHours,
            closingHours,
            available,
        };
        console.log(name, openingHours, closingHours, available);
        axios.put(`http://localhost:8000/api/shops/${id}`, updatedShop)
            .then((response) => {
                if (response.status === 200) {
                    setToastMessage("Boutique modifiée avec succès");
                    setToastType("bg-success text-white");
                    setShowToast(true);

                } else {
                    setToastMessage("Impossible de modifier la boutique");
                    setToastType("bg-danger text-white");
                    setShowToast(true);
                }
            })
            .catch((error) => {
                setToastMessage("Impossible de modifier la boutique");
                setToastType("bg-danger text-white");
                setShowToast(true);
            });
    };

    const handleBack = () => {
        navigate('/shops');
    };

    return (
        <>
            <h1 style={{textAlign: "center", marginTop: "4%"}}>Modifier la boutique</h1>
            <div style={{ margin: '5%', padding: '2%', border: '1px solid', borderRadius: '15px' }}>
                <form onSubmit={handleSubmit}>
                    <div className="mb-3">
                        <label htmlFor="name" className="form-label">Nom de la boutique:</label>
                        <input
                            type="text"
                            id="name"
                            className="form-control"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                        />
                    </div>
                    <div className="mb-3">
                        <label htmlFor="openingHours" className="form-label">Horaires d'ouverture :</label>
                        <input
                            type="time"
                            id="openingHours"
                            className="form-control"
                            value={openingHours}
                            onChange={(e) => setOpeningHours(e.target.value)}
                        />
                    </div>
                    <div className="mb-3">
                        <label htmlFor="closingHours" className="form-label">Horaires de fermeture :</label>
                        <input
                            type="time"
                            id="closingHours"
                            className="form-control"
                            value={closingHours}
                            onChange={(e) => setClosingHours(e.target.value)}
                        />
                    </div>
                    <div className="mb-3 form-check">
                        <input
                            type="checkbox"
                            id="available"
                            className="form-check-input"
                            checked={available}
                            onChange={(e) => setAvailable(e.target.checked)}
                        />
                        <label htmlFor="available" className="form-check-label">Congé :</label>
                    </div>
                    <button type="submit" className="btn btn-primary">Modifier la boutique</button>
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

export default UpdateShopForm;