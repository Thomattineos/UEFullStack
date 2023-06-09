import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Table, Dropdown, Form } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

function ProductModal() {
    const [products, setProducts] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [sort, setSort] = useState('name');
    const [searchProduct, setSearchProduct] = useState("");

    const navigate = useNavigate();

    const handleAddProduct = () => {
        navigate('/createProduct');
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
        fetch(`http://localhost:8080/api/products?page=${currentPage}&limit=8${sort ? `&sortBy=${sort}` : ''}&search=${searchProduct}`)
            .then(response => response.json())
            .then(data => {
                setProducts(data.products);
                setTotalPages(data.pagination.totalPages);
            });
    }, [currentPage, sort, searchProduct]);

    const updateProduct = (id) => {
        console.log("id=" + id);
        navigate(`/${id}/updateProduct`);
    }

    const deleteProduct = (id) => {
        fetch(`http://localhost:8080/api/products/${id}`, {
            method: 'DELETE',
        }).then(() => {
            fetch(`http://localhost:8080/api/products?page=${currentPage}&limit=8`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.products.length === 0) {
                        setCurrentPage(prev => prev - 1);
                        setTotalPages(prev => prev - 1);
                    }
                    setProducts(data.products);
                    setTotalPages(data.pagination.totalPages);
                });
        }).catch(error => console.error(error));
    }

    return (
        <>
            <h1 style={{ textAlign: "center", margin: "2%" }}>Liste des produits</h1>
            <div style={{ paddingRight: '5%', paddingLeft: '5%' }}>
                <div style={{ display: "flex" }}>
                    <div>
                        <Dropdown>
                            <Dropdown.Toggle variant="primary" id="dropdown-sort">Trier par : {
                                sort === "name" ? "Nom" :
                                    sort === "price" ? "Prix" : "Aucun"
                            }
                            </Dropdown.Toggle>

                            <Dropdown.Menu>
                                <Dropdown.Item eventKey="name" onClick={() => setSort("name")}>
                                    Nom
                                </Dropdown.Item>
                                <Dropdown.Item eventKey="creationDate" onClick={() => setSort("price")}>
                                    Prix
                                </Dropdown.Item>
                            </Dropdown.Menu>
                        </Dropdown>
                    </div>
                    <div style={{ marginLeft: "1%", width: "100%" }}>
                        <Form>
                            <Form.Control
                                type="text"
                                placeholder="Rechercher un produit..."
                                value={searchProduct}
                                onChange={({ currentTarget: input }) => setSearchProduct(input.value)}
                            />
                        </Form>
                    </div>
                </div>
                <Table striped bordered hover style={{ textAlign: 'center', marginTop: "1%" }}>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((product) => (
                            <tr key={product.id}>
                                <td width={'30%'}>{product.name}</td>
                                <td>{product.price} €</td>
                                <td width={'30%'}>{product.description}</td>
                                <td>
                                    <button className="btn btn-primary" style={{ marginRight: '5%' }} onClick={() => updateProduct(product.id)}>Modifier</button>
                                    <button className="btn btn-danger" onClick={() => deleteProduct(product.id)}>Supprimer</button>
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
                <button className="btn btn-primary mt-3" onClick={handleAddProduct}>
                    Ajouter un produit
                </button>
            </div>
        </>
    );
}

export default ProductModal;