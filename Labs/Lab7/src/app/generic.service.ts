import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';

import { Observable, of } from 'rxjs';
import { catchError, map, tap } from 'rxjs';
import { Product } from './model/product';
import { Category } from './model/category';

@Injectable({
    providedIn: 'root'
})
export class GenericService {
    private backendUrl = 'http://localhost/Lab6/';

    httpOptions = {
        headers: new HttpHeaders({
            'Content-Type': 'application/json'
        })
    };

    constructor(private http: HttpClient) { }

    fetchCategories() : Observable<Category[]> {
        return this.http.get<{ categories: string[] }>(this.backendUrl + "getCategories.php")
            .pipe(
                map(response => response.categories.map((name) => ({ name }))),
                catchError(this.handleError<Category[]>('fetchCategories', []))
      );
    }

    fetchProducts(categoryName: string, page: number) : Observable<Product[]> {
        let params = new HttpParams()
            .set('Category', categoryName)
            .set('Page', page.toString());

        return this.http.get<{ products: Product[], totalPages: number }>(this.backendUrl + "getProducts.php", { params })
        .pipe(
            map(response => response.products.map(product => ({
            id: +product.id,
            name: product.name,
            price: +product.price,
            category: product.category
            }))),
            catchError(this.handleError<Product[]>('fetchProductsByCategory', []))
        );
    }

    handleError<T>(operation = 'operation', result?: T) {
        return (error: any): Observable<T> => {
            console.error(error);
            return of(result as T);
        };
    }
}
