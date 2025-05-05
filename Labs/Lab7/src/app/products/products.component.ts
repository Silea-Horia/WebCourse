import { Component, OnInit } from '@angular/core';
import { Product } from '../model/product';
import { NgFor } from '@angular/common';

@Component({
    selector: 'app-products',
    imports: [NgFor],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];

    constructor() {}

    ngOnInit(): void {
        
    }
}
