#include <stdlib.h>
#include <stdio.h>
#include <limits.h>
#include <string.h>
#include "index-tools.h"
#include "define.h"

/* fungsi membuat tabel hash */
HashList *createHash()
{
	HashList *hashTable;
	int i;

	if( HASHSIZE < 1 ) {
		return NULL;
	}

	hashTable = malloc(sizeof(HashList));
	if( hashTable == NULL ) {
		printf("Memory allocation for Hash List fails...\n");
		return NULL;
	}

	hashTable->table = malloc(sizeof(HashData)*HASHSIZE);
	if( hashTable->table == NULL )
	{
		printf("Memory allocation for Hash Data fails...\n");
		return NULL;
	}

	for( i = 0; i < HASHSIZE; i++ )
	{
		hashTable->table[i] = NULL;
	}

	hashTable->size = HASHSIZE;

	return hashTable;
}

/* fungsi memasukkan data ke dalam HashList */
int setData ( HashList *hashTable, char *key, long int docno )
{
	int nilaiHash = 0;
	HashData *dataBaru = NULL;
	HashData *next = NULL;
	HashData *last = NULL;

	//Mencari Hash Key
	nilaiHash = hashing( hashTable, key );

	next = hashTable->table[ nilaiHash ];

	while( next != NULL && next->key != NULL && strcmp( key, next->key ) > 0 )
	{
		last = next;
		next = next->next;
	}

	if( next != NULL && next->key != NULL && strcmp( key, next->key ) == 0 ) {
		insertList(&(next->list),docno);
		return 0; /* tidak ada penambambah kata unik yang baru */
	} else {
		dataBaru = hashDataBaru( key );

		if( next == hashTable->table[ nilaiHash ] ) {
			dataBaru->next = next;
			hashTable->table[ nilaiHash ] = dataBaru;
		} else if ( next == NULL ) {
			last->next = dataBaru;
		} else {
			dataBaru->next = next;
			last->next = dataBaru;
		}

		insertList(&(dataBaru->list),docno);
		return 1;
	}
}

/* fungsi untuk mencari key untuk hash */
int hashing( HashList *hashTable, char *key )
{
	unsigned long int hashval = 6781;
	int a;
	while( (a = *key++) )
	{
		hashval = ((hashval << 5) + hashval) + a;
	}
	return hashval % hashTable->size;
}


HashData *hashDataBaru( char *key)
{
	HashData *dataBaru;
	char *term;

	if( ( dataBaru = malloc( sizeof( HashData ) ) ) == NULL )
	{
		printf("Memory allocation for Hash Data fails...\n");
		return NULL;
	}

	term = (char *) malloc(strlen(key)+1);
  strcpy(term,key);

	if( ( dataBaru->key = term ) == NULL )
	{
		return NULL;
	}
	dataBaru->list.head=NULL;
  dataBaru->list.size=0;
	dataBaru->next = NULL;

	return dataBaru;
}

/* fungsi memasukkan data ke dalam linkedlist */
int insertList(ListHead *ls, long int docno)
{
  ListNodePtr new;
	ListNodePtr next = ls->head;

  /* if it is the same document, then just update the frequency, without having to
     create new linked list node */

	while((next!=NULL) && ((next->docno) != docno))
	{
		next = next->next;
	}

  if((next!=NULL) && ((next->docno) == docno))
  {
    (next->freq)++;
  }
  else
  {
    new = (ListNodePtr) malloc(sizeof(ListNode));
    if(new==NULL)
    {
      printf("Memory allocation for linked list node fails...\n");
      return 0;
    }
    new->docno=docno;
    new->freq=1;
    new->next=ls->head;
    ls->head=new;
    ls->size++;
  }
  return 1;
}

static int totalTerm=0;

void hashTraversal(HashList *hashTable, int jumlahKata, FILE *finv, FILE *fvoc, FILE *fpar)
{
	/* menalokasikan memori penampung isi hash */
	HashData **sortedHash = malloc(jumlahKata * sizeof(HashData*));
	int i,idx=0;
	for(i=0;i<HASHSIZE;i++)
	{
		HashDataPtr p = hashTable->table[i];
		while (p)
		{
			sortedHash[idx++] = p;
			p = p->next;
		}
	}

	/* melakukan pengurutan data menggunakan quick sort */
	qsort(sortedHash, jumlahKata, sizeof(HashData*), compare_node);

	for(i=0; i<jumlahKata; i++)
	{
		cetakFile(sortedHash[i], finv, fvoc);
	}
	fwrite(&totalTerm, sizeof(long int),1,fpar);
	free(sortedHash);
	printf("Total Term : %d\n",totalTerm);
}

/* fungsi untuk membandingkan kata */
int compare_node(const void *left, const void *right)
{
	const HashDataPtr kiri = *(HashData **)left;
	const HashDataPtr kanan = *(HashData **)right;
	return strcmp(kiri->key,kanan->key);
}

/* procedure untuk membuat file*/
void cetakFile(HashData *pDataHash, FILE *finv, FILE *fvoc)
{
  if(pDataHash!=NULL)
  {
    long int offset;
    int totalList;

    offset=ftell(finv);
    totalList = readList(&(pDataHash->list), finv);
    fprintf(fvoc,"%s %d %ld\n",pDataHash->key, totalList, offset);
    totalTerm++;
  }
}

/* fungsi membaca linked list */
int readList(ListHead *ls, FILE *finv)
{
  int totalvalue=0;
  ListNodePtr current=ls->head;

  while(current!=NULL)
  {
    fwrite(&(current->docno),sizeof(long int),1,finv);
    fwrite(&(current->freq),sizeof(long int),1,finv);
    current=current->next;
    totalvalue+=2;
  }
  return totalvalue;
}

/* procedure untuk membersihkan memori HashList */
void freeHash(HashList *hashTable)
{
  int i;
  for(i = 0; i < HASHSIZE; i++)
	{
    if(hashTable->table[i] != NULL)
		{
			HashDataPtr current = hashTable->table[i];
	    HashDataPtr delete = current;

	    while(delete != NULL)
			{
	      current = delete->next;
				freeList(&(delete->list));
				free(delete->key);
	      free(delete);
	      delete = current;
	    }
    }
  }
	free(hashTable->table);
	free(hashTable);
}

/*procedure untuk membersihkan memori LinkedList */
void freeList(ListHead *pListHead)
{
  if(pListHead != NULL)
	{
    ListNodePtr current = pListHead->head;
    ListNodePtr delete = current;

    while(delete != NULL)
		{
      current = delete->next;
      free(delete);
      delete = current;
    }
  }
}
