import { Component, OnInit } from '@angular/core';
import { NgFor } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';

@Component({
    selector: 'app-products',
    imports: [NgFor],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getProducts();
    }

    getProducts():void {
        let category: string = 'Tech';
        let page: number = 1;
        this.genericService.fetchProducts(category, page)
            .subscribe(products => this.products = products);
    }
}
