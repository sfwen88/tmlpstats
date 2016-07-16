
const REPLACE_ITEM = 'sortable_collection/replace_item'
const REPLACE_COLLECTION = 'sortable_collection/replace_collection'
const CHANGE_SORT_CRITERIA = 'sortable_collection/change_sort_criteria'


export default class SortableCollection {
    constructor({name, key_prop='id', sort_by=null, sorts=new Map()}) {
        if (!sort_by) {
            sort_by = key_prop
        }
        if (!sorts.forEach) {
            throw 'SortableCollection: sorts must be an iterable'
        }
        var sortsLookup = {}
        sorts.forEach((v) => {
            sortsLookup[v.key] = v
        })
        this.sorts = sorts
        this.sortsLookup = sortsLookup
        this.name = name
        this.key_prop = key_prop
        this.default_state = {
            meta: {
                sort_by: sort_by
            },
            collection: {},
            sortedKeys: []
        }
    }

    // Action creators
    replaceItem(item) {
        return {type: REPLACE_ITEM, payload: {collection_name:this.name, item}}
    }

    replaceCollection(collection) {
        return {type: REPLACE_COLLECTION, payload: {collection_name:this.name, collection}}
    }

    changeSortCriteria(sort_by) {
        return {type: CHANGE_SORT_CRITERIA, payload: {collection_name:this.name, sort_by}}
    }

    // The reducer. Works as a factory to avoid having to bind `this` manually
    reducer() {
        return (state=this.default_state, action) => {
            if (action.payload && action.payload.collection_name == this.name) {
                var { sortedKeys, collection } = state
                switch (action.type) {
                case REPLACE_ITEM:
                    var item = action.payload.item
                    collection = Object.assign({}, state.collection)
                    collection[item[this.key_prop]] = item
                    sortedKeys = this.rebuildSort(state.meta.sort_by, collection)
                    state = Object.assign({}, state, {collection, sortedKeys})
                    break
                case REPLACE_COLLECTION:
                    collection = this.ensureCollection(action.payload.collection)
                    sortedKeys = this.rebuildSort(state.meta.sort_by, collection)
                    state = Object.assign({}, state, {collection, sortedKeys})
                    break
                case CHANGE_SORT_CRITERIA:
                    var meta = Object.assign({}, state.meta, {sort_by: action.payload.sort_by})
                    sortedKeys = this.rebuildSort(action.payload.sort_by, state.collection)
                    state = Object.assign({}, state, {meta, sortedKeys})
                    break
                }
            }
            return state
        }
    }

    // Utilities / Helpers for working with collection

    // iterItems is a handy way to iterate this collection.
    // the function callback is called with parameters (item, key, index)
    //
    // The state must be passed in, because this reducer doesn't hold on to the state.
    iterItems(state, callback) {
        for (var i = 0; i < state.sortedKeys.length; i++) {
            var key = state.sortedKeys[i]
            callback(state.collection[key], key, i)
        }
    }


    // Internal functionality

    rebuildSort(sortBy, collection) {
        var tmp = []
        for (var key in collection) {
            tmp.push(collection[key])
        }
        tmp.sort(this.sortsLookup[sortBy].comparator)

        // Phase 2: replace the value with the sort key... unknown if this is a good idea.
        for (var i = 0; i < tmp.length; i++) {
            tmp[i] = tmp[i][this.key_prop]
        }
        return tmp
    }

    ensureCollection(collection) {
        if (Array.isArray(collection)) {
            var newCollection = {}
            collection.forEach((v) => {
                newCollection[v[this.key_prop]] = v
            })
            return newCollection
        }
        return collection
    }
}

export function compositeKey(pairs) {
    return (a, b) => {
        var n
        for (var i = 0; i < pairs.length; i++) {
            var key = pairs[i][0]

            switch (pairs[i][1]) {
            case 'string':
                n = a[key].localeCompare(b[key])
                break
            case 'number':
                n = a[key] - b[key]
                break
            }
            if (n != 0) {
                return n
            }

        }
        return 0
    }
}
