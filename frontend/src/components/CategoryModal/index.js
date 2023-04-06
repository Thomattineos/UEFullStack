import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Table } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Prev } from 'react-bootstrap/esm/PageItem';

function CategoryModal() {
    const [categories, setCategories] = useState([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);

    const navigate = useNavigate();

    const handleAddCategory = () => {
        navigate('/createCategory');
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
        fetch(`http://localhost:8080/api/categories?page=${currentPage}&limit=8`)
            .then(response => response.json())
            .then(data => {
                setCategories(data.categories);
                setTotalPages(data.pagination.totalPages);
            });
    }, [currentPage]);

    const updateCategory = (id) => {
        console.log("id=" + id);
        navigate(`/${id}/updateCategory`);
    }

    const deleteCategory = (id) => {
        fetch(`http://localhost:8080/api/categories/${id}`, {
            method: 'DELETE',
        }).then(() => {
            fetch(`http://localhost:8080/api/categories?page=${currentPage}&limit=8`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if(data.categories.length === 0) {
                    setCurrentPage(prev => prev - 1 );
                    setTotalPages(prev => prev - 1);
                }
                setCategories(data.categories);
                setTotalPages(data.pagination.totalPages);
            });
        }).catch(error => console.error(error));
    }

    return (
        <>
            <h1 style={{textAlign: "center", marginTop: "4%"}}>Liste des catégories</h1>
            <div style={{ paddingTop: '4%', paddingRight: '5%', paddingLeft: '5%' }}>
                <Table striped bordered hover style={{ textAlign: 'center' }}>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {categories.map((category) => (
                            <tr key={category.id}>
                                <td width={'60%'}>{category.name}</td>
                                <td>
                                    <button className="btn btn-primary" style={{ marginRight: '5%' }} onClick={() => updateCategory(category.id)}>Modifier</button>
                                    <button className="btn btn-danger" onClick={() => deleteCategory(category.id)}>Supprimer</button>
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
                <button className="btn btn-primary mt-3" onClick={handleAddCategory}>
                    Ajouter une catégorie
                </button>
            </div>
        </>
    );
}

export default CategoryModal;