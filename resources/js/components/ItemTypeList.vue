<template>
    <div id="itemListComponent" class="col-md-6 mx-auto">
        <div class="form-group input-group x-auto" ref="newTypeGroup">
            <input v-model="newTypeName"
                    placeholder="New Type" 
                    class="form-control-sm form-control input-group-append" />
            <button class="btn btn-sm btn-success input-group-append" v-on:click="addItemType">
                <i class="fa fa-plus fa-sm"></i>
            </button>
        </div>
        <table class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th></th>
                    <th>Type Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="itemTypes.length > 0">
                <tr v-for="(itemType, i) in itemTypes" 
                    v-bind:typeId="itemType.id">
                    <td class="text-center">{{ i+1 }}</td>
                    <td>
                        <input v-if="itemType.editing" 
                                class="form-control-sm form-control"
                                v-model="itemType.name" />
                        <span v-else>{{ itemType.name }}</span> 
                    </td>
                    <td class="text-center">
                        <div v-if="itemType.editing">
                            <button class="btn btn-sm"
                                    v-bind:class="{ 'btn-success': itemType.status, 'btn-danger': !itemType.status }"
                                    v-on:click="itemType.status = !(itemType.status)">
                                {{ itemType.status? "Active" : "Disabled" }}
                            </button>
                        </div>
                        <span v-else>{{ itemType.status? "Active" : "Disabled" }}</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary"
                                v-on:click="editItemType(itemType, i)"
                                v-if="!itemType.editing">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary"
                                v-on:click="addItemType(itemType, i)"
                                v-if="itemType.editing">
                            Save
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4">No Item Types Added</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "item-type-list",
        data: function(){
            return {
                itemTypes: [],
                newTypeName: 'ABC',
                editType: null
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            editItemType: function(thisItemType, index){
                thisItemType.editing = true;
                console.log(thisItemType);
                this.newTypeName = thisItemType.name;
                this.newTypeName = '';
                /*this.editType = {
                    originalTypeObject: thisItemType,
                    index
                };*/
                // this.goto('newTypeGroup');
            },
            addItemType: async function (itemType, index) {
                if(itemType) {
                    itemType.editing = false;
                    let res = await axios
                            .post('{{ route("saveItemType") }}', {
                                    _token,
                                    itemType
                                });
                    Vue.set(
                        this.itemTypes, 
                        index, 
                        Object.assign(itemType, 
                            {name: this.newTypeName})
                        );
                } else {
                    if(!this.newTypeName) return false;
                    console.log("To Add", this.newTypeName);
                    let res = await axios
                            .post('{{ route("saveItemType") }}', {
                                    _token,
                                    itemType: {
                                        name: this.newTypeName 
                                    }
                                });
                    this.itemTypes.push({name: this.newTypeName, status: true});
                }

                this.newTypeName = "";
            },
            goto(refName) {
                var element = this.$refs[refName];
                console.log(element);
                var top = element.offsetTop;
                document.getElementById('content-wrapper').scrollTo(0, top);
            }
        }
    }
</script>