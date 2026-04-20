#include <stdio.h>

int main() {
    int pass;
    printf("Enter password: ");
    scanf("%d", &pass);

    if (pass == 1234)
        printf("Correct!\n");
    else
        printf("Wrong!\n");

    return 0;
}
