import {Feed} from "./Feed";
import {Flower} from "./Flower";

export class FeedStockCounterCsv {
    totalStock(feed: Feed): number {
        const rows = feed.content.split('\n').slice(1);
        const flowers = rows.map(row => {
            const fields = row.split(',');
            return new Flower(parseInt(fields[0]), fields[1], parseInt(fields[2]));
        })

        const totalStock = flowers.reduce((acc, flower) => {
            return acc + flower.totalStock();
        }, 0)

        return totalStock;
    }
}