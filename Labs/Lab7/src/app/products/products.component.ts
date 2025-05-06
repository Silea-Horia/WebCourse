import { Component, Input, OnInit } from '@angular/core';
import { NgFor } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';
import { Category } from '../model/category';

@Component({
    selector: 'app-products',
    imports: [NgFor],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];
    categories: Category[] = [];
    selectedCategory?: Category;

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getCategories();
    }

    getCategories(): void {
        this.genericService.fetchCategories()
            .subscribe(categories => this.categories = categories);
    }

    onSelect(category: Category): void {
        console.log("selected " + category.name);
        this.selectedCategory = category;
    }

    getProductsByCategory(categoryName: string):void {
        let page: number = 1;
        this.genericService.fetchProducts(categoryName, page)
            .subscribe(products => this.products = products);
    }
}
