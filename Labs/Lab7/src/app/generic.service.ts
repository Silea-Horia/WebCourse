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
    private backendUrl: string = 'http://localhost/Lab6/';
    private totalPages: number = 1;

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

    fetchProducts(category: Category, page: number) : Observable<{products: Product[], totalPages: number}> {
        let params = new HttpParams()
            .set('Category', category.name)
            .set('Page', page.toString());

            return this.http.get<{ products: Product[], totalPages: number }>(this.backendUrl + "getProducts.php", { params })
            .pipe(
              map(response => {
                this.totalPages = response.totalPages;
                return {
                  products: response.products.map(product => ({
                    id: +product.id,
                    name: product.name,
                    price: +product.price,
                    category: product.category
                  })),
                  totalPages: response.totalPages
                };
              }),
              catchError(this.handleError<{ products: Product[], totalPages: number }>('fetchProducts', { products: [], totalPages: 1 }))
            );
    }

    updateProduct(product: Product, newName: string, newPrice: string, newCategory: string): Observable<{ success: boolean, error?: string }> {
        let body = new HttpParams()
            .set('id', product.id.toString())
            .set('pname', newName)
            .set('price', newPrice)
            .set('category', newCategory);

        const headers = new HttpHeaders({
            'Content-Type': 'application/x-www-form-urlencoded'
        });

        return this.http.post<{ success: boolean, error?: string }>(this.backendUrl + "updateProduct.php", body.toString(), { headers })
            .pipe(
            map(response => {
                console.log('Update Product Response:', response);
                return response;
            }),
            catchError(this.handleError<{ success: boolean, error?: string }>('updateProduct', { success: false, error: 'Request failed' }))
            );
    }

    handleError<T>(operation = 'operation', result?: T) {
        return (error: any): Observable<T> => {
            console.error(error);
            return of(result as T);
        };
    }
}
